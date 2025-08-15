<?php

use Indapoint\EmailTemplate\EmailTemplate;
use Carbon\Carbon;

class EmailTempTest extends TestCase
{
    public $header;
    
    /**
     *@test
     */
    public function setUp()
    {
        parent::setUp();
        
        $this->header = ['Accept' => 'application/json'];
        $response = $this->call('POST', 'api/v1/oauth/signin/', [
            'email'  => 'jigar@indapoint.com',
            'password'  => 'Adm@2021',
            'user_type'  => 'ADMIN'
            ], [], [], $this->header);
        
        if ($response->status() == 200) {
            $token  = json_decode($response->getContent())->data->access_token;
            $this->header['HTTP_Authorization'] = 'Bearer '.$token;
        }
        $this->assertEquals(200, $response->status());
    }
    
    /**
     * Test to email List
     */
    public function testEmailList()
    {

        //get email and its gives error like UNAUTHORIZED
        $headers['HTTP_Authorization'] = 'Bearer fdssdf';
        $response = $this->get('api/email_template/list/', [], $headers)
            ->seeJson([
                'status_code' => 401
             ]);
        
        $response = $this->call('GET', 'api/email_template/list/', [
            ], [], [], $this->header);
        $this->assertEquals(200, $response->status());
    }

    /**
     * Test update Email
     */
    public function testEmailUpdate()
    {
        $email = EmailTemplate::orderBy('id', 'desc')->first();
        //get validation error
        $response = $this->put('api/email_template/update/'.$email->id, [
            'status'  => 'Active',
        ], $this->header)
            ->seeJson([
                'status_code' => 422
             ]);
        
        //update faq
        $response = $this->call('PUT', 'api/email_template/update/'.$email->id, [
            'email_key'  => $email->email_key,
            'email_subject'  => $email->email_subject,
            'email_body'  => $email->email_body
            ], [], [], $this->header);
        $this->assertEquals(200, $response->status());
    }
    
    /**
     * Show email
     */
    public function testEmailShow()
    {
        $email = EmailTemplate::orderBy('id', 'desc')->first();
        
        $response = $this->call('GET', 'api/email_template/show/'.$email->id, [
            ], [], [], $this->header);
        $this->assertEquals(200, $response->status());
    }
}
