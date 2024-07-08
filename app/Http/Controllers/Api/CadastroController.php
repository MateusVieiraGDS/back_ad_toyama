<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\UploadTrait;
use App\Models\Batismo;
use App\Models\Consagracao;
use App\Models\DadosComplementar;
use App\Models\Endereco;
use App\Models\File;
use App\Models\Membro;
use App\Models\State;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CadastroController extends Controller
{
    use UploadTrait;
    /**
     * estou finalizando o cadastro, estava fazendo o negocio do estado, falta testar e ve como ficou, tem a situação da imagem também,
     * para a edição e os componentes não controlados
     * 
     */

    public function NewMember(Request $request){       

        return response()->json([
            'status' => 'success',
            'message' => 'Cadastro realizado com sucesso!',
            'data' => $request->all(),
        ]);

        $user = User::newUser(
            $request->input('dadosPessoais@nome'),
            $request->input('dadosEnderecoContato@email'),
            String::random(20),
            4
        );
        
        $filesUploaded = $this->uploadManyFiles($request, [
            'dadosPessoais@imageFile34', 
            'dadosPessoais@imageFileDoc', 
            'dadosPessoais@imageFileNasc',
            'dadosPessoais@imageFileCas',
            'dadosPessoais@imageFileDiv',
            'dadosPessoais@imageFileObt',
            'dadosCongregacaoBatismo@certBatFile',
        ]);

        $member = Membro::create([
            'uuid' => Str::uuid(),
            'rg' => $request->input('dadosPessoais@rg'),
            'cpf' => $request->input('dadosPessoais@cpf'),
            'nasc' => $request->input('dadosPessoais@dataNasc'),
            'sexo' => $request->input('dadosPessoais@sexo'),
            'estado_civil' => $request->input('dadosPessoais@estadoCivil'),
            'nat_state_id' => $request->input('dadosPessoais@estado'),
            'nat_city_id' => $request->input('dadosPessoais@cidade'),
            'rg_state_id' => $request->input('dadosPessoais@rgUf'),
            'user_id' => $user->id,
            'grupo_id' => $request->input('dadosCongregacaoBatismo@grupo'),
            'congregacao_id' => $request->input('dadosCongregacaoBatismo@congregacao'),
            'file_cert_nascimento' => $filesUploaded['dadosPessoais@imageFileNasc'],
            'file_doc_image' => $filesUploaded['dadosPessoais@imageFileDoc'],
            'file_foto'  => $filesUploaded['dadosPessoais@imageFile34'],
        ]);

        DadosComplementar::create([
            'membro_id' => $member->id,
            'nome_pai' => $request->input('dadosPessoais@nomePai'),
            'nome_mae' => $request->input('dadosPessoais@nomeMae'),
            'ministerio_anterior' => $request->input('dadosCongregacaoBatismo@minisAnt')
        ]);

        $bat_ministerio = $request->input('dadosCongregacaoBatismo@localBatismo');
        Batismo::create([
            'membro_id' => $member->id,
            'data_batismo' => $request->input('dadosCongregacaoBatismo@dataBatismo'),
            'ministerio' => $bat_ministerio == 'self' ? null : $bat_ministerio,
            'file_cert_batismo' => $filesUploaded['dadosPessoais@certBatFile'],
        ]);


        $state = State::findOrFail($request->input('dadosEnderecoContato@estado'));)
        Endereco::create([
            'membro_id' => $member->id,
            'cep' => $request->input('dadosEnderecoContato@cep'),
            'rua' => $request->input('dadosEnderecoContato@rua'),
            'numero' => $request->input('dadosEnderecoContato@numero'),
            'complemento' => $request->input('dadosEnderecoContato@compl'),
            'bairro' => $request->input('dadosEnderecoContato@bairro'),
            'cidade' => $request->input('dadosEnderecoContato@cidade'),
            'estado' => $request->input('dadosEnderecoContato@estado'),
        ]);

        $this->processarConsagracoes($member->id, $request);
    }

    private function uploadManyFiles(Request $request, array $filesNames){
        $files = [];
        foreach($filesNames as $filesName){
            if($request->hasFile($filesName)){
                $file = $request->file($filesName);
                $files[$filesName] = File::create([
                    'pathname' => $this->upload($file),
                    'mime' => $file->getMimeType(),
                    'size' => $file->getSize()
                ]);
            }
        }
        return $files;
    }

    public function processarConsagracoes(int $membroId, Request $request)
    {

        $data = $request->all();

        $consagracoes = [];

        foreach ($data as $key => $value) {
            if (preg_match('/dadosConsagracao@ARRAY\[(\d+)\]@(\w+)/', $key, $matches)) {
                $index = $matches[2];
                $field = $matches[3];
                
                // Inicializa a estrutura se ainda não existir
                if (!isset($consagracoes[$index])) {
                    $consagracoes[$index] = [];
                }

                // Armazena o valor no campo correspondente
                $consagracoes[$index][$field] = $value;
            }
        }

        // Processar cada consagração
        foreach ($consagracoes as $consagracao) {
            $cargoId = $consagracao['cargoId'] ?? null;
            $dataCon = $consagracao['dataCon'] ?? null;
            $ministerio = $consagracao['ministerio'] ?? null;
            $certConFile = $consagracao['certConFile'] ?? null;

            // Upload do arquivo se existir
            $fileCertConsagracao = null;
            if ($certConFile && $request->hasFile($certConFile)) {
                $file = $request->file($certConFile);
                $fileCertConsagracao = File::create([
                    'pathname' => $this->upload($file),
                    'mime' => $file->getMimeType(),
                    'size' => $file->getSize()
                ]);
            }

            // Criar a entrada no banco de dados
            Consagracao::create([
                'data_consagracao' => Carbon::createFromFormat('d/m/Y', $dataCon),
                'ministerio' => $ministerio == 'self' ? null : $ministerio,
                'membro_id' => $membroId,
                'cargo_id' => $cargoId,
                'file_cert_consagracao' => $fileCertConsagracao->id,
                'situacao' => 'ATIVO',
            ]);
        }
    }
}
