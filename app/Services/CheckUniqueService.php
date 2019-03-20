<?php

namespace App\Services;

use App\Repositories\AccountPhoneRepository;
use App\Repositories\AdminsRepository;
use App\Repositories\BankCardRepository;
use App\Repositories\UsersRepository;
use App\Repositories\AccountBankCardsRepository;

class CheckUniqueService
{
    protected $adminsRepository;
    protected $usersRepository;
    protected $accountPhoneRepository;
    protected $accountBankCardsRepository;
    protected $bankCardRepository;

    public function __construct(BankCardRepository $bankCardRepository, AdminsRepository $adminsRepository, UsersRepository $usersRepository, AccountPhoneRepository $accountPhoneRepository,AccountBankCardsRepository $accountBankCardsRepository)
    {
        $this->adminsRepository = $adminsRepository;
        $this->usersRepository = $usersRepository;
        $this->accountPhoneRepository = $accountPhoneRepository;
        $this->accountBankCardsRepository=$accountBankCardsRepository;
        $this->bankCardRepository=$bankCardRepository;
    }


    /**
     * 唯一验证
     * @param string $table_name
     * @param string $field
     * @param string $value
     * @param int|null $id
     * @return bool
     */
    public function check(string $table_name, string $field, string $value, int $id = null, string $name = null)
    {
        switch ($table_name) {
            case 'admins':
                $result = $this->adminsRepository->searchOne($field, $value, $id);
                break;

            case 'users':
                $result = $this->usersRepository->searchCheck($field, $value, $id);
                break;

            case 'bank_cards':
                $result = $this->bankCardRepository->searchCheck($field, $value, $id);
                break;

            case 'account_phones':
                $result = $this->accountPhoneRepository->searchCheck($id, $value,$name);
                break;

            case 'account_bank_cards':
                $result = $this->accountBankCardsRepository->searchCheck($field, $value, $id);
                break;

            default :
                return false;
        }

        return $result = $result ? false : true;
    }

}
