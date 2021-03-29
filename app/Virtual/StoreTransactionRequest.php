<?php

/**
 * @OA\Schema(
 *      title="Solicitação de cadastro da movimentação",
 *      description="Armazenar dados do corpo da solicitação da movimentação",
 *      type="object",
 *      required={"user_id","date_transaction","description","fl_operation","fl_reversal","value"}
 * )
 */
class StoreTransactionRequest 
{

    /**
     * @OA\Property(
     *     title="ID do Usuário",
     *     description="Cõdigo do usuário proprietário do movimento",
     *     example=1
     * )
     *
     * @var integer
     */
    public $user_id;

    /**
     * @OA\Property(
     *     title="Data de movimentação",
     *     description="Data de movimentação",
     *     example="2021-01-18",
     *     format="date",
     *     type="string"    
     * )
     *
     * @var \DateTime
     */
    public $date_transaction;

    /**
     * @OA\Property(
     *      title="Descrição",
     *      description="Descrição do lançamento",
     * )
     *
     * @var string
     */
    public $description;

    /**
     * @OA\Property(
     *      title="Operação",
     *      description="Informa se é uma operação de Débito (0) ou Crédito (1)",
     *      example="1"
     * )
     *
     * @var integer
     */
    private $fl_operation;

    /**
     * @OA\Property(
     *      title="Indicador de estorno",
     *      description="Informa se é uma operação de estorno, onde, 0 indica movimento normal, e 1 indica movimento de estorno",
     *      example="0",
     * )
     *
     * @var integer
     */
    private $fl_reversal;

    /**
     * @OA\Property(
     *      title="Valor",
     *      description="Valor absoluto da movimentação. Não informar valor negativo",
     * )
     *
     * @var number
     */
    private $value;

    /**
     * @OA\Property(
     *     title="Código de transação original",
     *     description="Código da transação original que está sendo estornada",
     *     example="123",
     * )
     *
     * @var integer
     */
    private $transaction_id;
}