<?php

namespace App\Services;

use App\Repositories\QuotaLogsRepository;

class QuotalogService
{
    protected $quotaLogsRepository;

    /**
     * QuotalogService constructor.
     * @param QuotaLogsRepository $quotaLogsRepository
     */
    public function __construct(QuotaLogsRepository $quotaLogsRepository)
    {
        $this->quotaLogsRepository = $quotaLogsRepository;
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function add(array $data){
        return $this->quotaLogsRepository->add($data);
    }

    /**
     * @param array $data
     * @param int $page
     * @return mixed
     */
    public function searchPage(array $data, int $page)
    {
        return $this->quotaLogsRepository->searchPage($data,$page);
    }

    /**
     * @param array $data
     * @param int $quota_type
     * @return mixed
     */
    public function searchNum(array $data, int $quota_type)
    {
        return $this->quotaLogsRepository->searchNum($data, $quota_type);
    }
}