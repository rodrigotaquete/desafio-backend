<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;

use App\Http\Resources\UserResource;
use App\Http\Resources\UserExtractResource;
use App\Http\Resources\UserBalanceResource;

use DB;

use App\Models\User;
use App\Models\Transaction;

class UserController extends Controller
{

    /**
     *  @OA\Get(
     *      path="/users",
     *      tags={"Usuários"},
     *      summary="Listar todos os usuários",
     *      description="Lista completa de usuários paginada",
     *      security={{"bearerAuth":{}}},
     *      @OA\Response(
     *          response="200",
     *          description="Sucesso na consulta",
     *          @OA\JsonContent(ref="#/components/schemas/User")
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Sem autorização",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Acesso proibido"
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="Erro interno"
     *      )
     *  )
     *
     * @return Json
    */
    public function index()
    {
        // Selecionando todos os usuários, ordenando por ID e paginando a cada 5 registros
        $users = User::orderBy("id", "asc")->paginate(5);

        return response()->json($users);
    }

    /**
     * @OA\Get(
     *      path="/users/id/{id}",
     *      operationId="geUserById",
     *      tags={"Usuários"},
     *      summary="Listar dados de usuário",
     *      description="Retornar dados de um único usuário",
     *      @OA\Parameter(
     *          name="id",
     *          description="Código do usuário",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response="200",
     *          description="Sucesso na consulta",
     *          @OA\JsonContent(ref="#/components/schemas/User")
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Erro desconhecido",
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Sem autorização",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Acesso proibido"
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="Erro interno"
     *      )
     * )
     *
     * @return Json
     */
    public function show($id)
    {
        // Buscando registro solicitado
        $user = User::findOrFail($id);

        return (new UserResource($user))->response()->setStatusCode(200);
    }

    /**
     *  @OA\Post(
     *      path="/users",
     *      operationId="storeUser",
     *      tags={"Usuários"},
     *      summary="Cadastrar usuário",
     *      description="Cadastrar usuários",
     *      security={{"bearerAuth":{}}},
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/StoreUserRequest")
     *      ),
     *      @OA\Response(
     *          response="201",
     *          description="Sucesso no cadastro",
     *          @OA\JsonContent(ref="#/components/schemas/User")
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Erro desconhecido",
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Sem autorização",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Acesso proibido"
     *      ),
     *      @OA\Response(
     *          response=406,
     *          description="Informações inválidas"
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="Erro interno"
     *      )
     *  )
     *
     * @return Json
     */
    public function store(StoreUserRequest $request)
    {
        // Recebendo dados
        $data = $request->all();

        // Verificação de email existente
        $email = User::where("email", "=", $data["email"])->count();

        if ($email > 0) {
            return response()->json(array("msg" => "Email informado já existente no cadastro"), 406);
        } else {
            // Tratamento específico para campo password
            $data["password"] = bcrypt($data["password"]);

            // Calculando se usuário tem mais de 18 anos
            $limite = date("Y-m-d", strtotime('-18 year -1 day'));
            $nascimento = date("Y-m-d");
            if ($data["birthday"] != "") {
                $nascimento = date("Y-m-d", strtotime($data["birthday"]));
            }

            // Se a data de nascimento for mais recente que o limite de 18 anos, cancela operação
            if ($limite < $nascimento) {
                return response()->json(array("msg" => "Usuário não possui mais de 18 anos"), 406);
            } else {
                // Criando registro
                $user = User::create($data);

                return (new UserResource($user))
                    ->response()
                    ->setStatusCode(Response::HTTP_CREATED);
            }
        }
    }

    /**
     *  @OA\Put(
     *      path="/users",
     *      operationId="updateUser",
     *      tags={"Usuários"},
     *      summary="Alterar saldo inicial usuário",
     *      description="Alterar saldo inicial de usuários",
     *      security={{"bearerAuth":{}}},
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/UpdateUserRequest")
     *      ),
     *      @OA\Response(
     *          response="200",
     *          description="Sucesso na alteração",
     *          @OA\JsonContent(ref="#/components/schemas/User")
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Erro desconhecido",
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Sem autorização",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Acesso proibido"
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="Erro interno"
     *      )
     *  )
     *
     * @return Json
     */
    public function update(UpdateUserRequest $request)
    {
        // Recebendo dados
        $data = $request->all(); 

        // Buscando registro solicitado
        $user = User::findOrFail($data["id"]);

        // Atualizando saldo inicial
        $user->update(['opening_balance' => $data["opening_balance"]]);

        return (new UserResource($user))
            ->response()
            ->setStatusCode(200);
    }
    
    /**
     * @OA\Delete(
     *      path="/users/{id}",
     *      operationId="deleteUser",
     *      tags={"Usuários"},
     *      summary="Excluir um usuário existente",
     *      description="Excluir um registro de usuário",
     *      @OA\Parameter(
     *          name="id",
     *          description="Código do usuário",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response=204,
     *          description="Sucesso na exclusão",
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Sem autorização",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Acesso proibido"
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Usuário não encontrada",
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="Erro interno"
     *      )
     * )
     */
    public function destroy($id)
    {
        // Verificando movimentações do usuário
        $transaction = Transaction::where("user_id", "=", $id)->count();

        // Se o usuário possui movimentações, cancela processo
        if ($transaction > 0) {
            return response()->json(array("msg" => "Usuário possui movimentações"), 403);
        } else {
            // Buscando registro solicitado
            $user = User::findOrFail($id);

            // Excluindo registro
            $user->delete();

            return response()->json(null, 204);
        }
    }

    /**
     * @OA\Get(
     *      path="/users/extract/{id}",
     *      operationId="geUserExtractById",
     *      tags={"Usuários"},
     *      summary="Extrato de usuário com dados do cadastro e suas movimentações",
     *      description="Retornar dados de um único usuário com todas as suas movimentações",
     *      @OA\Parameter(
     *          name="id",
     *          description="Código do usuário",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response="200",
     *          description="Sucesso na consulta",
     *          @OA\JsonContent(ref="#/components/schemas/UserExtract")
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Erro desconhecido",
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Sem autorização",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Acesso proibido"
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="Erro interno"
     *      )
     * )
     *
     * @return Json
     */
    public function extract($id)
    {
        // Buscando registro solicitado
        $user = User::findOrFail($id);

        return (new UserExtractResource($user))->response()->setStatusCode(200);
    }

    /**
     * @OA\Get(
     *      path="/users/balance/{id}",
     *      operationId="geUserBalanceById",
     *      tags={"Usuários"},
     *      summary="Saldo fiancneiro final de usuário",
     *      description="Saldo fiancneiro final de usuário",
     *      @OA\Parameter(
     *          name="id",
     *          description="Código do usuário",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response="200",
     *          description="Sucesso na consulta",
     *          @OA\JsonContent(ref="#/components/schemas/UserBalance")
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Erro desconhecido",
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Sem autorização",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Acesso proibido"
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="Erro interno"
     *      )
     * )
     *
     * @return Json
     */
    public function balance($id)
    {
        $balance = User::select(DB::raw('(sum((case when transactions.fl_operation = 0 then (transactions.value * (-1)) else transactions.value end)) + users.opening_balance) as balance'))
                        ->join("transactions", "transactions.user_id", "=", "users.id")
                        ->where("users.id", "=", $id)
                        ->groupBy('users.opening_balance')
                        ->first();

        return (new UserBalanceResource($balance))->response()->setStatusCode(200);
    }
}
