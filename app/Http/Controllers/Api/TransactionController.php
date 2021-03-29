<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

use App\Http\Requests\StoreTransactionRequest;
use App\Http\Resources\TransactionResource;
use App\Models\Transaction;
use App\Models\User;

class TransactionController extends Controller
{
    /**
     *  @OA\Get(
     *      path="/transactions",
     *      tags={"Movimentações"},
     *      summary="Listar todas as movimentações",
     *      description="Lista completa de movimentações paginada",
     *      security={{"bearerAuth":{}}},
     *      @OA\Response(
     *          response="200",
     *          description="Sucesso na consulta",
     *          @OA\JsonContent(ref="#/components/schemas/Transaction")
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
        // Selecionando todos os usuários, ordenando por ID e paginando a cada 50 registros
        $transactions = Transaction::orderBy("id", "asc")->paginate(50);

        return response()->json($transactions);
    }

    /**
     * @OA\Get(
     *      path="/transactions/id/{id}",
     *      operationId="geTransactionById",
     *      tags={"Movimentações"},
     *      summary="Listar dados de movimentação",
     *      description="Retornar dados de uma única movimentação",
     *      @OA\Parameter(
     *          name="id",
     *          description="Código da movimentação",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response="200",
     *          description="Sucesso na consulta",
     *          @OA\JsonContent(ref="#/components/schemas/Transaction")
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
        $transaction = Transaction::findOrFail($id);

        return (new TransactionResource($transaction))->response()->setStatusCode(200);
    }

    /**
     *  @OA\Post(
     *      path="/transactions",
     *      operationId="storeTransaction",
     *      tags={"Movimentações"},
     *      summary="Cadastrar movimentação",
     *      description="Cadastrar movimentações na base de dados",
     *      security={{"bearerAuth":{}}},
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/StoreTransactionRequest")
     *      ),
     *      @OA\Response(
     *          response="201",
     *          description="Sucesso no cadastro",
     *          @OA\JsonContent(ref="#/components/schemas/Transaction")
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
    public function store(StoreTransactionRequest $request)
    {
        // Recebendo dados
        $data = $request->all();

        if ($data["value"] <= 0.00) {
            return response()->json(array("msg" => "Valor da movimentação deve ser maior que zero"), 406);
        } else {
            // Verificando se usuário informado existe
            $user = User::where('id', '=', $data["user_id"])->count();

            // Se o usuário não foi encontrato, cancela operação
            if ($user == 0) {
                return response()->json(array("msg" => "Usuário informado não encontrato"), 406);
            } else {
                // Caso não seja um lançamento de estorno, limpa o campo de transação original
                if ($data["fl_reversal"] == 0) {
                    $data["transaction_id"] = null;
                }

                // Verificando se a transação original foi informada e se existe
                $origin = true;
                if (! is_null($data["transaction_id"])) {
                    if (Transaction::where('id', '=', $data["transaction_id"])->count() == 0) {
                        $origin = false;
                    }
                }

                // Se a transação original foi informada e é inválida, cancela operação
                if (!$origin) {
                    return response()->json(array("msg" => "Movimentação de origem do estorno não encontrata"), 406);
                } else {
                    // Criando registro
                    $transaction = Transaction::create($data);

                    return (new TransactionResource($transaction))->response()->setStatusCode(Response::HTTP_CREATED);
                }
            }
        }
    }

    /**
     * @OA\Delete(
     *      path="/transactions/{id}",
     *      operationId="deletTransaction",
     *      tags={"Movimentações"},
     *      summary="Excluir uma movimentação existente",
     *      description="Excluir um registro de movimentação",
     *      @OA\Parameter(
     *          name="id",
     *          description="Código da movimentação",
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
        // Buscando registro solicitado
        $transaction = Transaction::findOrFail($id);

        // Excluindo registro
        $transaction->delete();

        return response()->json(null, 204);
    }
   
}
