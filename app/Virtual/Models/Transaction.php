<?php

/**
 * @OA\Schema(
 *     title="Movimentações",
 *     description="Modelo de Movimentações",
 *     @OA\Xml(
 *         name="Transaction"
 *     )
 * )
 */
class Transaction
{
    /**
     * @OA\Property(
     *     title="ID",
     *     description="Cõdigo do movimento",
     *     format="int64",
     *     example=1
     * )
     *
     * @var integer
     */
    private $id;

    /**
     * @OA\Property(
     *     title="ID do Usuário",
     *     description="Cõdigo do usuário proprietário do movimento",
     *     format="int64",
     *     example=1
     * )
     *
     * @var integer
     */
    private $user_id;

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
    private $date_transaction;

    /**
     * @OA\Property(
     *      title="Descrição",
     *      description="Descrição do lançamento",
     *      example="Pagamento de conta de telefone"
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
    public $fl_operation;

    /**
     * @OA\Property(
     *      title="Indicador de estorno",
     *      description="Informa se é uma operação de estorno, onde, 0 indica movimento normal, e 1 indica movimento de estorno",
     *      example="0"
     * )
     *
     * @var integer
     */
    protected $fl_reversal;

    /**
     * @OA\Property(
     *      title="Valor",
     *      description="Valor absoluto da movimentação. Não informar valor negativo",
     * )
     *
     * @var number
     */
    protected $value;

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

    /**
     * @OA\Property(
     *     title="Data de cadastro",
     *     description="Data de cadastro do registro",
     *     example="2020-01-27 17:50:45",
     *     format="datetime",
     *     type="string"    
     * )
     *
     * @var \DateTime
     */
    private $created_at;

    /**
     * @OA\Property(
     *     title="Data de alteração",
     *     description="Data de alteração do registro",
     *     example="2020-01-27 17:50:45",
     *     format="datetime",
     *     type="string"
     * )
     *
     * @var \DateTime
     */
    private $updated_at;

}