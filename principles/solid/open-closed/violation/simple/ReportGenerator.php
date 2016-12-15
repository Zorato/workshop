<?php

/**
 * Class ReportGenerator
 */
class ReportGenerator
{

    const TYPE_PDF = 'pdf';
    const TYPE_GOOGLE_ANALYTICS = 'ga';

    /**
     * @param array $reportData Comes from $_POST['data']
     * @param string $reportType Comes from $_POST['type']
     * @return mixed
     * @throws InvalidArgumentException
     */
    public function generate($reportData, $reportType)
    {
        // At first we validate report data to throw exception as early as possible.
        if (!isset($reportData['period'])) {
            throw new InvalidArgumentException('Missing report period.');
        }
        
        // Then we modify report data, because it comes with "invalid" period format.
        $reportData['period'] = strtotime($reportData['period']);

        // Then we decide which actual generator to use:
        switch ($reportType) {
            case self::TYPE_PDF:
                /** @var ActualGenerator $generator */
                $generator = Mage::getModel('report/generator_pdf');
                $report = $generator->generate($reportData);
                break;
            case self::TYPE_GOOGLE_ANALYTICS:
                /** @var ActualGenerator $generator */
                $generator = Mage::getModel('report/generator_google');
                $report = $generator->generate($reportData);
                break;
            default:
                throw new InvalidArgumentException('Invalid report type');
        }

        // At last we add some more logic/info to report before returning it.
        $report->setGenerationTimestamp(time());

        return $report;
    }
    
}