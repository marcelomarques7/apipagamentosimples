# Api Pagamento Simples

Api para pagamentos simples entre usuários comuns e logistas, logista não transferem valor, apenas recebem.<br>
Foco no fluxo de transferencias, não tem autenticação.<br>
Transação consistente, caso algo dê errado o valor volta para a conta do cliente pagador e a transação é cancelada.<br>
## Documentação

### Endpoints

http://APP_URL/api/cadastro<br>
Verbo POST<br>
Cadastro de usuário<br>
Necessário mandar:<br>
nome, sobrenome, cpf_cnpj, email, senha, saldo
Tipo de usuário definido caso manda cpf 11 caracteres ou cnpj 14 caracteres <br><br>


http://APP_URL/api/users<br>
Verbo GET<br>
Retorna todos os usuários<br><br>


http://APP_URL/api/user/{id}<br>
Verbo GET<br>
Retorna um usuário<br><br>


http://APP_URL/api/user/{id}<br>
Verbo PUT<br>
Atualiza um usuário<br>
Necessário mandar:<br>
nome, sobrenome, email, senha<br><br>


http://APP_URL/api/user/{id}<br>
Verbo DELETE<br>
Deleta um usuário<br><br>


http://APP_URL/api/transferir<br>
Verbo POST<br>
Necessário mandar:<br>
pagador_id, beneficiario_id, valor


http://APP_URL/api/extrato/{id}<br>
Verbo GET<br>
Retorna extrato de pagamentos, recebimentos e saldo disponível
