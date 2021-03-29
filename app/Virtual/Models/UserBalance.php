<?php

/**
 * @OA\Schema(
 *     title="Saldo final de Usuário",
 *     description="Modelo de Saldo de Usuário",
 *     @OA\Xml(
 *         name="User"
 *     )
 * )
 */
class UserBalance
{
    /**
     * @OA\Property(
     *      title="Saldo Final",
     *      description="Valor do saldo final do usuário",
     * )
     *
     * @var number
     */
    private $balance;

}