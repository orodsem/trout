Feature: Testing the JobOffer and Profile REST API

#    Job Offer
Scenario: Finding a Job Offer by id
    Given that I want to find a "JobOffer"
    When I request "/jobOffer/get/1"
    Then the "company" property equals "trout"

Scenario: Publishing a Job Offer by id changes the status
    Given that I want to find a "JobOffer"
    When I request "/jobOffer/get/2"
    Then the "status" property equals "0"
    When I request "/jobOffer/publish/2"
    When I request "/jobOffer/get/2"
    Then the "status" property equals "1"

Scenario: Publishing a Job Offer by id set expiry date to 15 days after the current date.
    Given that I want to find a "JobOffer"
    When I request "/jobOffer/get/2"
    Then the "expiry_date" property equals "expiryDate()"

###    Profile
Scenario: Finding a Profile by id
    Given that I want to find a "Profile"
    When I request "/profile/get/1"
    Then the "first_name" property equals "Alice"

Scenario: Validating Profile entity attributes
    Given that I want to find a "Profile"
    When I request "/profile/get/1"
    And the type of the "id" property is numeric
    And the type of the "first_name" property is string
    And the type of the "last_name" property is string
    And the type of the "job_offers" property is array
    And the type of the "languages" property is array
    And the type of the "experiences" property is array

Scenario: Deleting a Profile by id, if profile found return 204
    Given that I want to delete a "Profile"
    When I request "/profile/delete/1"
    Then the response status code should be 204




