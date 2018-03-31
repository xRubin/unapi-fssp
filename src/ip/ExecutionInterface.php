<?php

namespace unapi\fssp\ip;

interface ExecutionInterface
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

    /**
     * @param string $debtor
     * @param string $proceeding
     * @param string $proceedingDocument
     * @param string $finishReason
     * @param string $reason
     * @param string $executionDepartment
     * @param string $executionJudge
     * @return ExecutionInterface
     */
    public static function toDto(string $debtor, string $proceeding, string $proceedingDocument, string $finishReason, string $reason, string $executionDepartment, string $executionJudge): ExecutionInterface;
}