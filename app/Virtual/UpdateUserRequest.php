<?php

/**
 * @OA\Schema(
 *      title="Solicitação de alteração de saldo inicial de usuário",
 *      description="Alterar dados do corpo da solicitação de usuário",
 *      type="object",
 *      required={"id","opening_balance"}
 * )
 */
class UpdateUserRequest 
{

    /**
     * @OA\Property(
     *      title="ID",
     *      description="Código do usuário",
     *      example="1"
     * )
     *
     * @var integer
     */
    public $id;

    /**
     * @OA\Property(
     *      title="Saldo Inicial",
     *      description="Valor do saldo inicial do usuário",
     * )
     *
     * @var number
     */
    private $opening_balance;
    
}