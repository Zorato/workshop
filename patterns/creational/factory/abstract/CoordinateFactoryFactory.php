<?php declare(strict_types=1);


/**
 * Class CoordinateFactoryFactory
 */
class CoordinateFactoryFactory implements AbstractCoordinateFactory
{

    /**
     * @var AboveSeaCoordinateFactory
     */
    private $aboveSeaLevelFactory;

    /**
     * @var PlainCoordinateFactory
     */
    private $plainFactory;

    /**
     * CoordinateFactoryFactory constructor.
     *
     * @param PlainCoordinateFactory $plainFactory
     * @param AboveSeaCoordinateFactory $aboveSeaLevelFactory
     */
    public function __construct(PlainCoordinateFactory $plainFactory, AboveSeaCoordinateFactory $aboveSeaLevelFactory)
    {
        $this->plainFactory = $plainFactory;
        $this->aboveSeaLevelFactory = $aboveSeaLevelFactory;
    }

    /**
     * @inheritDoc
     */
    public function create($parameter): CoordinateFactory
    {
        switch ($parameter) {
            case 'A':
                return $this->plainFactory;
            case 'B':
                return $this->aboveSeaLevelFactory;
            default:
                throw new InvalidArgumentException('Invalid parameter');
        }
    }


}
