<?php

namespace App\Http\Controllers;

use SoapHeader;
use DOMDocument;
use Artisaninweb\SoapWrapper\SoapWrapper;
use App\Soap\Request\GetConversionAmount;
use App\Soap\Response\GetConversionAmountResponse;

class SoapController
{
  /**
   * @var SoapWrapper
   */
  protected $soapWrapper;

  /**
   * @var SoapWrapper
   */
  protected $soapHeader;

  /**
   * SoapController constructor.
   *
   * @param SoapWrapper $soapWrapper
   */
  public function __construct(SoapWrapper $soapWrapper)
  {
    $this->soapWrapper = $soapWrapper;
    $this->soapWrapper->add('CLF', function ($service) {
      $service
        ->wsdl('http://services.clfdistribution.com:8080/CLFWebOrdering_Test/WebOrdering.asmx?WSDL')
        ->trace(true);
    });

    $sh = array( 'AuthenticationToken' => '', 'ErrorMessage' => '');
    $this->soapHeader = new SoapHeader('http://services.clfdistribution.com/CLFWebOrdering', 'WebServiceHeader', $sh);
    $response = $this->soapWrapper->call('CLF.GetAuthenticationToken', array($this->soapHeader),
    [
      'Username' => 'motherearthhealthfoods',
      'Password' => 'steNEDeC4t'
    ]);

    $sh = array( 'AuthenticationToken' => (string)$response->GetAuthenticationTokenResult, 'ErrorMessage' => '' );
    $this->soapHeader = new SoapHeader('http://services.clfdistribution.com/CLFWebOrdering', 'WebServiceHeader', $sh);
  }

  /**
   * Use the SoapWrapper
   */
  public function getProductCodes()
  {
    return $this->soapWrapper->call('CLF.GetProductCodes', array($this->soapHeader))->GetProductCodesResult;
  }

  public function getProducts($pc = null) {
      $dom=new DOMDocument();
      if (!$pc) {
        $pc = ['productCodesXml' => $this->getProductCodes()];
      } else {
        $pc = ['productCodesXml' => $pc];
      }
      $sc_res = $this->soapWrapper->call('CLF.GetProductData', array($this->soapHeader), $pc);
      $dom->loadXML($sc_res->GetProductDataResult);
      $root=$dom->documentElement;
      $data=$root->getElementsByTagName('Product');
      return $data;
  }

  public function getProductsExtended($pc = null) {
      $dom=new DOMDocument();
      if (!$pc) {
        $pc = ['productCodesXml' => $this->getProductCodes()];
      } else {
        $pc = ['productCodesXml' => $pc];
      }
      $sc_res = $this->soapWrapper->call('CLF.GetProductExtendedData', array($this->soapHeader), $pc);
      $dom->loadXML($sc_res->GetProductExtendedDataResult);
      $root=$dom->documentElement;
      $data=$root->getElementsByTagName('ProductData');
      return $data;
  }

  public function getProductAttributes($pc = null) {
      $dom=new DOMDocument();
      if (!$pc) {
        $pc = ['productCodesXml' => $this->getProductCodes()];
      } else {
        $pc = ['productCodesXml' => $pc];
      }
      $sc_res = $this->soapWrapper->call('CLF.GetProductAttributes', array($this->soapHeader), $pc);
      $dom->loadXML($sc_res->GetProductAttributesResult);
      $root=$dom->documentElement;
      $data=$root->getElementsByTagName('ProductAttribute');
      return $data;
  }

  public function getProductAttribute($sku) {
    $dom=new DOMDocument();
    $pc = ['productCodesXml' => '<ProductCodes><Code>'.$sku.'</Code></ProductCodes>'];
    $sc_res = $this->soapWrapper->call('CLF.GetProductAttributes', array($this->soapHeader), $pc);
    $dom->loadXML($sc_res->GetProductAttributesResult);
    $root=$dom->documentElement;
    $data=$root->getElementsByTagName('ProductAttribute')->item(0);
    return $data;
  }
}
