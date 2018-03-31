<?php

namespace unapi\fssp\ip;

use unapi\fssp\ip\requests;

class FormFactory
{
    /**
     * @param requests\RequestInterface $request
     * @return array|null
     */
    public static function getForm(requests\RequestInterface $request): ?array
    {
        if ($request instanceof requests\ByIndividualRequestInterface)
            return static::getFormByIndividual($request);

        if ($request instanceof requests\ByLegalRequestInterface)
            return static::getFormByLegal($request);

        if ($request instanceof requests\ByExecutionRequestInterface)
            return static::getFormByExecution($request);

        if ($request instanceof requests\ByDocumentRequestInterface)
            return static::getFormByDocument($request);
    }

    /**
     * @param requests\ByIndividualRequestInterface $request
     * @return array
     */
    protected static function getFormByIndividual(requests\ByIndividualRequestInterface $request): array
    {
        return [
            'extended' => 1,
            'variant' => 1,
            'region_id' => [
                0 => $request->getRegionKey()
            ],
            'last_name' => $request->getFullName()->getSurname(),
            'first_name' => $request->getFullName()->getName(),
            'patronymic' => $request->getFullName()->getPatronymic(),
            'date' => $request->getBirthdate() ? $request->getBirthdate()->format('d.m.Y') : null,
        ];
    }

    /**
     * @param requests\ByLegalRequestInterface $request
     * @return array
     */
    protected static function getFormByLegal(requests\ByLegalRequestInterface $request): array
    {
        return [
            'extended' => 1,
            'variant' => 2,
            'region_id' => [
                0 => $request->getRegionKey()
            ],
            'drtr_name' => $request->getName(),
            'address' => $request->getAddress(),
        ];
    }

    /**
     * @param requests\ByExecutionRequestInterface $request
     * @return array
     */
    protected static function getFormByExecution(requests\ByExecutionRequestInterface $request): array
    {
        return [
            'extended' => 1,
            'variant' => 3,
            'region_id' => [
                0 => 77
            ],
            'ip_number' => $request->getExecutionNumber(),
        ];
    }

    /**
     * @param requests\ByDocumentRequestInterface $request
     * @return array
     */
    protected static function getFormByDocument(requests\ByDocumentRequestInterface $request): array
    {
        return [
            'extended' => 1,
            'variant' => 4,
            'region_id' => [
                0 => $request->getRegionKey()
            ],
            'id_number' => $request->getExecutionDocument(),
            'id_type' => [
                0 => null
            ],
            'id_issuer' => null,
        ];
    }
}