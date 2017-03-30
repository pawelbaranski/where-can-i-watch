Feature: Searching for broadcasts

  Scenario: Searching for broadcasts that returns results
    Given I am on "/search"
     When I fill in "broadcast_search[query]" with "Rambo"
      # wait for demo purpose only
      And I wait for 5 seconds
      And I press "Search"
     Then I should be on "/search"
      And I should see "TVP 1"
      And I should see "Rambo"
      # wait for demo purpose only
      And I wait for 5 seconds