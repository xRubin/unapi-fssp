<?php

namespace unapi\fssp\physical;

interface ResponseExecutionInterface
{
    /**
     * @return string
     */
    public function getDebtor(): string;

    /**
     * @return string
     */
    public function getProceeding(): string;

    /**
     * @return string
     */
    public function getProceedingDocument(): string;

    /**
     * @return string
     */
    public function getFinishReason(): string;

    /**
     * @return string
     */
    public function getReason(): string;

    /**
     * @return string
     */
    public function getExecutionDepartment(): string;

    /**
     * @return string
     */
    public function getExecutionJudge(): string;
}