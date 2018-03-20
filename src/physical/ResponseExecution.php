<?php

namespace unapi\fssp\physical;

class ResponseExecution implements ResponseExecutionInterface
{
    /** @var string */
    private $debtor;
    /** @var string */
    private $proceeding;
    /** @var string */
    private $proceedingDocument;
    /** @var string */
    private $finishReason;
    /** @var string */
    private $reason;
    /** @var string */
    private $executionDepartment;
    /** @var string */
    private $executionJudge;

    /**
     * ResponseExecution constructor.
     * @param string $debtor
     * @param string $proceeding
     * @param string $proceedingDocument
     * @param string $finishReason
     * @param string $reason
     * @param string $executionDepartment
     * @param string $executionJudge
     */
    public function __construct(string $debtor, string $proceeding, string $proceedingDocument, string $finishReason, string $reason, string $executionDepartment, string $executionJudge)
    {
        $this->debtor = $debtor;
        $this->proceeding = $proceeding;
        $this->proceedingDocument = $proceedingDocument;
        $this->finishReason = $finishReason;
        $this->reason = $reason;
        $this->executionDepartment = $executionDepartment;
        $this->executionJudge = $executionJudge;
    }

    /**
     * @return string
     */
    public function getDebtor(): string
    {
        return $this->debtor;
    }

    /**
     * @return string
     */
    public function getProceeding(): string
    {
        return $this->proceeding;
    }

    /**
     * @return string
     */
    public function getProceedingDocument(): string
    {
        return $this->proceedingDocument;
    }

    /**
     * @return string
     */
    public function getFinishReason(): string
    {
        return $this->finishReason;
    }

    /**
     * @return string
     */
    public function getReason(): string
    {
        return $this->reason;
    }

    /**
     * @return string
     */
    public function getExecutionDepartment(): string
    {
        return $this->executionDepartment;
    }

    /**
     * @return string
     */
    public function getExecutionJudge(): string
    {
        return $this->executionJudge;
    }
}