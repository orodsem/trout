<?php
// features/bootstrap/RestContext.php
use Behat\Behat\Context\BehatContext;
use Symfony\Component\Yaml\Yaml;

/**
 * Rest context.
 */
class RestContext extends BehatContext
{
    private $restObject = null;
    private $restObjectType = null;
    private $restObjectMethod = 'get';
    private $client = null;
    /** @var Guzzle\Http\Message\Response $response  */
    private $response;
    private $requestUrl = null;

    private $parameters = array();

    /**
     * Initializes context.
     * Every scenario gets it's own context object.
     */
    public function __construct(array $parameters)
    {
        // Initialize your context here
        date_default_timezone_set('Australia/Sydney');
        $this->restObject = new stdClass();
        $this->client = new Guzzle\Service\Client();
        $this->parameters = $parameters;
    }

    public function getParameter($name)
    {
        if (count($this->parameters) === 0) {


            throw new \Exception('Parameters not loaded!');
        } else {

            $parameters = $this->parameters;
            return (isset($parameters[$name])) ? $parameters[$name] : null;
        }
    }

    /**
     * @Given /^that I want to make a new "([^"]*)"$/
     */
    public function thatIWantToMakeANew($objectType)
    {
        $this->restObjectType = lcfirst($objectType);
        $this->restObjectMethod = 'post';
    }

    /**
     * @Given /^that I want to find a "([^"]*)"$/
     */
    public function thatIWantToFindA($objectType)
    {
        $this->restObjectType = lcfirst($objectType);
        $this->restObjectMethod = 'get';
    }

    /**
     * @Given /^that I want to delete a "([^"]*)"$/
     */
    public function thatIWantToDeleteA($objectType)
    {
        $this->restObjectType = lcfirst($objectType);
        $this->restObjectMethod = 'delete';
    }

    /**
     * @Given /^that its "([^"]*)" is "([^"]*)"$/
     */
    public function thatTheItsIs($propertyName, $propertyValue)
    {
        $this->restObject->$propertyName = $propertyValue;
    }

    /**
     * @When /^I request "([^"]*)"$/
     */
    public function iRequest($pageUrl)
    {
        $baseUrl = $this->getParameter('base_url');
        $this->requestUrl = $baseUrl . $pageUrl;

        switch (strtoupper($this->restObjectMethod)) {
            case 'GET':
                $response = $this->client
                    ->get($this->requestUrl . '?' . http_build_query((array)$this->restObject))
                    ->send();
                break;
            case 'POST':
                $postFields = (array)$this->restObject;

                $response = $this->client
                    ->post($this->requestUrl, null, $postFields)
                    ->send();
                break;
            case 'DELETE':
                $response = $this->client
                    ->delete($this->requestUrl . '?' . http_build_query((array)$this->restObject))
                    ->send();
                break;
        }

        $this->response = $response;
    }

    /**
     * @Then /^the response is JSON$/
     */
    public function theResponseIsJson()
    {
        $data = json_decode($this->response->getBody(true));

        if (empty($data)) {
            throw new Exception("Response was not JSON\n" . $this->response);
        }
    }

    /**
     * @Given /^the response has a "([^"]*)" property$/
     */
    public function theResponseHasAProperty($propertyName)
    {
        $data = json_decode($this->response->getBody(true));
        $propertyParent = $this->restObjectType;

        if (!empty($data)) {
            if (!isset($data->$propertyParent->$propertyName)) {
                throw new Exception("Property '" . $propertyName . "' is not set!\n");
            }
        } else {
            throw new Exception("Response was not JSON\n" . $this->response->getBody(true));
        }
    }

    /**
     * @Then /^the "([^"]*)" property equals "([^"]*)"$$/
     */
    public function thePropertyEquals($propertyName, $propertyValue)
    {
        $data = json_decode($this->response->getBody(true));
        $propertyParent = $this->restObjectType;

        if (!empty($data)) {
            // to get the current time
            $propertyValue = $propertyValue === "expiryDate()" ? strtotime("+15 day 23:59:59") : $propertyValue;

            if (!isset($data->$propertyParent->$propertyName)) {
                throw new Exception("Property '" . $propertyName . "' is not set!\n");
            }
            if ($data->$propertyParent->$propertyName != $propertyValue) {
                throw new \Exception(
                    'Property value mismatch! (given: ' . $propertyValue . ', match: ' . $data->$propertyParent->$propertyName . ')'
                );
            }
        } else {
            throw new Exception("Response was not JSON\n" . $this->response->getBody(true));
        }
    }

    /**
     * @Given /^the type of the "([^"]*)" property is ([^"]*)$/
     */
    public function theTypeOfThePropertyIsNumeric($propertyName, $typeString)
    {
        $data = json_decode($this->response->getBody(true));
        $propertyParent = $this->restObjectType;

        if (!empty($data)) {
            if (!isset($data->$propertyParent->$propertyName)) {
                throw new Exception("Property '" . $propertyName . "' is not set!\n");
            }
            // check our type
            switch (strtolower($typeString)) {
                case 'numeric':
                    if (!is_numeric($data->$propertyParent->$propertyName)) {
                        throw new Exception(
                            "Property '" . $propertyName . "' is not of the correct type !\n"
                        );
                    }
                    break;
            }

        } else {
            throw new Exception("Response was not JSON\n" . $this->response->getBody(true));
        }
    }

    /**
     * @Then /^the response status code should be (\d+)$/
     */
    public function theResponseStatusCodeShouldBe($httpStatus)
    {
        if ((string)$this->response->getStatusCode() !== $httpStatus) {
            throw new \Exception(
                'HTTP code does not match ' . $httpStatus .
                ' (actual: ' . $this->response->getStatusCode() . ')'
            );
        }
    }

    /**
     * @Then /^echo last response$/
     */
    public function echoLastResponse()
    {
        $this->printDebug(
            $this->requestUrl . "\n\n" .
            $this->response
        );
    }
}