<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function cadastro(Request $request) {
        $array = ['error' => ''];

        // Validação de informações
        $rules = [
            'nome'          => 'required',
            'sobrenome'     => 'required',
            'cpf_cnpj'      => 'required | min:11 | max:14 | unique:users,cpf_cnpj',
            'email'         => 'required | email | unique:users,email',
            'senha'         => 'required',
            'saldo'         => 'numeric'
        ];
        $validator = Validator::make($request->all(), $rules);

        if($validator->fails()) {
            $array['error'] = $validator->errors();
            return $array;    
        }

        $nome = $request->input('nome');
        $sobrenome = $request->input('sobrenome');
        $cpf_cnpj = $request->input('cpf_cnpj');
        $email = $request->input('email');
        $senha = $request->input('senha');
        $saldo = $request->input('saldo');        
        
        // Verificando se é CPF ou CNPJ
        if(strlen($request->input('cpf_cnpj')) == 11)
        {
            $tipo_user_id = 1;
        } else if(strlen($request->input('cpf_cnpj')) == 14)
        {
            $tipo_user_id = 2;
        } else
        {
            $array['error'] = 'Informe um CPF ou CNPJ!';

            return $array;
        }

        // Criando novo usuário
        $newUser = new User();
        $newUser->nome = $nome;
        $newUser->sobrenome = $sobrenome;
        $newUser->cpf_cnpj = $cpf_cnpj;
        $newUser->email = $email;
        $newUser->senha = password_hash($senha, PASSWORD_DEFAULT);
        $newUser->tipo_user_id = $tipo_user_id;
        $newUser->saldo = $saldo;
        $newUser->save();
        
        $array['success'] = 'Usuário criado com sucesso!';
        $array['user'] = $newUser;

        return $array;
    }
}
