<?php

namespace App\Http\Controllers;

use App\Models\Transferencia;
use App\Models\User;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransferenciaController extends Controller
{
    public function transferir(Request $request)
    {
        $array = ['error' => ''];

        // Inicia transação
        DB::beginTransaction();

        // Buscar usuarios da transação
        $pagador = User::find($request->pagador);
        $beneficiario = User::find($request->beneficiario);

        // Validação se os usuários existem
        if ($pagador == "" || $beneficiario == "") {
            $array['error'] = 'Pagador e/ou Beneficiário inexistente';
            return $array;
        }

        $valor = $request->valor;
        $saldoPagador = $pagador->saldo;
        $saldoBeneficiario = $beneficiario->saldo;
        $saldoPagadorAnterior = $pagador->saldo;
        $saldoBeneficiarioAnterior = $beneficiario->saldo;

        // Validações para fazer a transferencia
        if ($pagador->tipo_user_id == 2) {
            $array['error'] = 'Pagador é um logista não pode fazer transferências';
            return $array;
        }
        if ($pagador == $beneficiario) {
            $array['error'] = 'Pagador e Beneficiário iguais';
            return $array;
        }
        if ($saldoPagador < $valor) {
            $array['error'] = 'Pagador não tem saldo para a transação';
            return $array;
        }

        // Serviço autorizador
        $autorizacao = new Client();
        $resAutorizacao = $autorizacao->request('GET', 'https://run.mocky.io/v3/8fafdd68-a090-496f-8c9a-3442cf30dae6');
        if ($resAutorizacao->getStatusCode() == 200) { // 200 OK
            $resAutorizacaoMessage = $resAutorizacao->getBody()->getContents();
            //dd($resAutorizacaoMessage);
            $array['autorization'] = $resAutorizacaoMessage;
            $newTransferencia = new Transferencia();
            $newTransferencia->valor = $valor;
            $newTransferencia->pagador_user_id = $pagador->id;
            $newTransferencia->beneficiario_user_id = $beneficiario->id;

            $pagador->saldo = $saldoPagador - $valor;
            $beneficiario->saldo = $saldoBeneficiario + $valor;

            $newTransferencia->save();
            $pagador->save();
            $beneficiario->save();
        }



        // Confirmar transação
        // Comparando se o SALDO ANTERIOR do PAGADOR é igual ao SALDO ATUAL mais o VALOR TRANSFERIDO
        // &&
        // Comparando se o SALDO ANTERIOR do BENEFICIÁRIO é igual ao SALDO ATUAL menos o VALOR TRANSFERIDO
        // Se sim, COMMIT
        // Se não, ROLLBACK e cancela transação
        if ($saldoPagadorAnterior == ($pagador->saldo + $valor) && $saldoBeneficiarioAnterior == ($beneficiario->saldo - $valor)) {
            DB::commit();

            // Notificação
            $notificacao = new Client();
            $resNotify = $notificacao->request('GET', 'http://o4d9z.mocklab.io/notify');
            $resNotifyMessage = $resNotify->getBody()->getContents();
            //dd($resNotifyMessage);

            $array['success'] = 'Transação concluída com sucesso!';
            $array['notify'] = $resNotifyMessage;
        } else {
            $array['error'] = 'Transação cancelada!';
            DB::rollBack();
        }

        return $array;
    }

    public function extrato($id)
    {
        $array = ['error' => ''];

        $user = User::find($id);

        $extrato = Transferencia::where('pagador_user_id', $id)
            ->orWhere('beneficiario_user_id', $id)->get();

        $saldoDisponivel = $user->saldo;

        $array['extrato'] = $extrato;
        $array['saldo'] = $saldoDisponivel;

        return $array;
    }
}
