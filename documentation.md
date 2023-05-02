FORMAT: 1A

# API

# AppApiV1ControllersSignUpController

## Register user [POST /]
Register a new user with a `email` and `password`.

+ Request (application/json)
    + Body

            {
                "email": "foo@foo.com",
                "password": "bar"
            }

+ Response 201 (application/json)
    + Body

            {
                "status": "ok",
                "token": "1234567890"
            }

+ Response 422 (application/json)
    + Body

            {
                "error": {
                    "email": [
                        "Email is already taken."
                    ]
                }
            }