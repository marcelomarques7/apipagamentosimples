<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function users(){
        $array = ['error' => ''];

        // Busca todos usuários
        $users = User::all();
        // Exibe usuários
        $array['users'] = $users;

        return $array;
    }

    public function user($id){
        $array = ['error' => ''];

        // Verifica se usuário existe
        $user = User::find($id);        
        if(empty($user)){
            $array = ['error' => 'Usuário inexistente!'];
            return $array;
        }
        
        // Exibe usuário
        $array['user'] = $user;

        return $array;
    }

    public function atualizar($id, Request $request){
        $array = ['error' => ''];
        
        // Verifica se usuário existe
        $user = User::find($id);    
        if(empty($user)){
            $array = ['error' => 'Usuário inexistente!'];
            return $array;
        }    

        // Validação de informações
        if($request->email == $user->email){
            $rules = $rules = [
                'email' => 'email',
            ];
        } else{
            $rules = [
                'email' => 'email | unique:users,email',
            ];
        }
        
        $validator = Validator::make($request->all(), $rules);

        if($validator->fails()) {
            $array['error'] = $validator->errors();
            return $array;    
        }
        
        $nome = $request->input('nome');
        $sobrenome = $request->input('sobrenome');
        $email = $request->input('email');
        $senha = $request->input('senha');

        // Atualizando usuário
        if($nome){
            $user->nome = $nome;    
        }
        if($sobrenome){
            $user->sobrenome = $sobrenome;    
        }
        if($email){
            $user->email = $email; 
        }
        if($senha){
            $user->senha = password_hash($senha, PASSWORD_DEFAULT);
        }
        $user->save();
        
        $array['success'] = 'Usuário atualizado com sucesso!';

        $array['user'] = $user;

        return $array;
    }

    public function deletar($id){
        $array = ['error' => ''];
        
        // Verifica se usuário existe
        $user = User::find($id);    
        if(empty($user)){
            $array = ['error' => 'Usuário inexistente!'];
            return $array;
        }

        $user->delete();

        $array['success'] = 'Usuário deletado com sucesso!';

        return $array;
    }
}
