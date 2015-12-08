<?php
namespace jones\novaposhta\tests;

use jones\novaposhta\Address;
use jones\novaposhta\http\ClientException;

/**
 * Class AddressTest
 */
class AddressTest extends TestCase
{
    /**
     * @var \jones\novaposhta\Address
     */
    private $model;

    protected function setUp()
    {
        $this->createApp();
        $requestFactory = $this->getRequestFactory();
        $this->model = new Address($requestFactory);
    }

    public function testGetAreas()
    {
        $this->request->expects(static::once())
            ->method('execute')
            ->willReturn([
                'success' => 'true',
                'data' => [
                    'item' => [
                        ['Description' => 'Kievskaya', 'Ref' => '000c2965ae0e'],
                        ['Description' => 'Odesskaya', 'Ref' => '8ejf4vb2c0e6'],
                    ]
                ],
                'warnings' => '',
                'info' => 'Api request info'
            ]);
        $response = $this->model->getAreas();
        static::assertEquals(2, count($response['item']));
        static::assertEquals('8ejf4vb2c0e6', $response['item'][1]['Ref']);
    }

    /**
     * @covers \jones\novaposhta\Address::getCities
     */
    public function testGetCities()
    {
        $this->request->expects(static::once())
            ->method('execute')
            ->willReturn([
                'success' => true,
                'data' => [
                    'item' => [
                        ['Description' => 'Kievskaya', 'Ref' => '000c2965ae0e', 'AreasCenter' => '001a92567626']
                    ]
                ],
                'warnings' => '',
                'info' => ''
            ]);
        $filter = 'Kiev';
        $response = $this->model->getCities($filter);
        static::assertEquals(1, count($response));
    }

    /**
     * @covers \jones\novaposhta\Address::call
     */
    public function testGetCitiesWithException()
    {
        $message = 'Test Exception';
        $exception = new ClientException($message);
        $this->request->expects(static::once())
            ->method('execute')
            ->willThrowException($exception);

        static::assertFalse($this->model->getCities());
        static::assertEquals($message, $this->model->getFirstError('getCities'));
    }

    /**
     * @covers \jones\novaposhta\Address::call
     */
    public function testDeleteWithFailedValidation()
    {
        $this->request->expects(static::never())
            ->method('build');
        $this->request->expects(static::never())
            ->method('execute');
        static::assertFalse($this->model->delete());
        static::assertEquals('Ref cannot be blank.', $this->model->getFirstError('Ref'));
    }

    /**
     * @covers \jones\novaposhta\Address::call
     */
    public function testUnSuccessDelete()
    {
        $id = '005056801329';
        $message = 'Address not deleted';
        $this->request->expects(static::once())
            ->method('execute')
            ->willReturn([
                'success' => false,
                'data' => [
                    'item' => [
                        'Ref' => '005056801329'
                    ]
                ],
                'errors' => $message
            ]);
        $this->model->Ref = $id;
        static::assertFalse($this->model->delete());
        static::assertEquals($message, $this->model->getFirstError('delete'));
    }

    /**
     * @covers \jones\novaposhta\Address::delete
     */
    public function testDelete()
    {
        $id = '0025909b4e33';
        $this->request->expects(static::once())
            ->method('execute')
            ->willReturn([
                'success' => true,
                'data' => [
                    'item' => [
                        'Ref' => $id
                    ]
                ],
                'warnings' => '',
                'info' => '',
            ]);
        $this->model->Ref = $id;
        static::assertTrue($this->model->delete());
    }

    /**
     * @covers \jones\novaposhta\Address::logWarnings
     */
    public function testLogWarnings()
    {
        $id = 'cb25d39b4em2';
        $this->request->expects(static::once())
            ->method('execute')
            ->willReturn([
                'success' => true,
                'data' => [
                    'item' => [
                        'Ref' => $id
                    ]
                ],
                'warnings' => 'Contractor address can\'t be deleted',
                'info' => '',
            ]);
        $this->model->Ref = $id;
        static::assertTrue($this->model->delete());
    }

    /**
     * @covers \jones\novaposhta\Address::getWarehouses
     */
    public function testGetWarehouses()
    {
        $this->request->expects(static::once())
            ->method('execute');
        /**
         * @TODO implement logic for asserts
         */

        $this->model->CityRef = '001a92567626';
        $this->model->getWarehouses();
    }
}
