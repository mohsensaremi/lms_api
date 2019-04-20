define({ "api": [
  {
    "type": "get",
    "url": "/me",
    "title": "me",
    "group": "Auth",
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "object",
            "optional": false,
            "field": "accessToken",
            "description": "<p>store it for auth required requests</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "user:",
          "content": "{ \"status\": 200, \"data\": {\"user\":{\"firstName\":\"sample\",\"lastName\":\"sample\",\"type\":\"instructor\",\"email\":\"sample@domain.com\"}} }",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "app/Http/Controllers/AuthController.php",
    "groupTitle": "Auth",
    "name": "GetMe",
    "header": {
      "fields": {
        "Auth": [
          {
            "group": "Auth",
            "type": "String",
            "optional": false,
            "field": "Authorization",
            "description": "<p>Authorization JSON web token.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Header-Example:",
          "content": "{ \"Authorization\": \"Bearer eyJ0eXAiOiJKV1QiLCJh...\" }",
          "type": "json"
        }
      ]
    }
  },
  {
    "type": "post",
    "url": "/login",
    "title": "login",
    "group": "Auth",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "email",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "password",
            "description": ""
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "accessToken",
            "description": "<p>store it for auth required requests</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "app/Http/Controllers/AuthController.php",
    "groupTitle": "Auth",
    "name": "PostLogin"
  },
  {
    "type": "post",
    "url": "/logout",
    "title": "logout",
    "group": "Auth",
    "version": "0.0.0",
    "filename": "app/Http/Controllers/AuthController.php",
    "groupTitle": "Auth",
    "name": "PostLogout",
    "header": {
      "fields": {
        "Auth": [
          {
            "group": "Auth",
            "type": "String",
            "optional": false,
            "field": "Authorization",
            "description": "<p>Authorization JSON web token.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Header-Example:",
          "content": "{ \"Authorization\": \"Bearer eyJ0eXAiOiJKV1QiLCJh...\" }",
          "type": "json"
        }
      ]
    }
  },
  {
    "type": "post",
    "url": "/register",
    "title": "register",
    "group": "Auth",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "email",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "password",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "passwordConfirmation",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "firstName",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "lastName",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "allowedValues": [
              "instructor",
              "student"
            ],
            "optional": false,
            "field": "type",
            "description": "<p>user type</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "accessToken",
            "description": "<p>store it for auth required requests</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "app/Http/Controllers/AuthController.php",
    "groupTitle": "Auth",
    "name": "PostRegister"
  },
  {
    "type": "get",
    "url": "/course/list",
    "title": "list",
    "description": "<p>return current user course list. if instructor: list of created courses. if student: list of joined courses</p>",
    "group": "Course",
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Object[]",
            "optional": false,
            "field": "data",
            "description": ""
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "status",
            "description": ""
          }
        ]
      },
      "examples": [
        {
          "title": "Instructor course list:",
          "content": "{ \"status\": 200, \"data\": [{\"id\":\"123\", \"title\":\"sample\", \"description\":\"sample\", \"images\":[{\"name\":\"sample.jpg\", \"url\":\"http://localhost/sample.jpg\"}]}] }",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "app/Http/Controllers/CourseController.php",
    "groupTitle": "Course",
    "name": "GetCourseList",
    "header": {
      "fields": {
        "": [
          {
            "group": "Paginate",
            "type": "Number",
            "optional": false,
            "field": "limit",
            "defaultValue": "15",
            "description": "<p>limit value for paginate query.</p>"
          },
          {
            "group": "Paginate",
            "type": "Number",
            "optional": false,
            "field": "skip",
            "defaultValue": "0",
            "description": "<p>skip value for paginate query.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Request-Example:",
          "content": "{ \"limit\": 10, \"skip\": 5 }",
          "type": "json"
        }
      ]
    }
  },
  {
    "type": "post",
    "url": "/course/submit",
    "title": "submit",
    "description": "<p>submit a course. if id provided in request update it, otherwise create new record with given data.</p>",
    "group": "Course",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "title",
            "description": "<p>course title.</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": true,
            "field": "description",
            "description": "<p>course description.</p>"
          },
          {
            "group": "Parameter",
            "type": "Object[]",
            "optional": true,
            "field": "images",
            "description": "<p>list of images. each object should contain name. objects returned from <code>/file/upload-temp</code> api should send here</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": true,
            "field": "password",
            "description": "<p>class password for join</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "app/Http/Controllers/CourseController.php",
    "groupTitle": "Course",
    "name": "PostCourseSubmit",
    "header": {
      "fields": {
        "Auth": [
          {
            "group": "Auth",
            "type": "String",
            "optional": false,
            "field": "Authorization",
            "description": "<p>Authorization JSON web token.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Header-Example:",
          "content": "{ \"Authorization\": \"Bearer eyJ0eXAiOiJKV1QiLCJh...\" }",
          "type": "json"
        }
      ]
    }
  },
  {
    "type": "post",
    "url": "/file/upload-temp",
    "title": "upload temp",
    "description": "<p>upload file to temp directory.</p>",
    "group": "File",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "File",
            "optional": false,
            "field": "file",
            "description": "<p>file for upload.</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "app/Http/Controllers/FileController.php",
    "groupTitle": "File",
    "name": "PostFileUploadTemp",
    "header": {
      "fields": {
        "Auth": [
          {
            "group": "Auth",
            "type": "String",
            "optional": false,
            "field": "Authorization",
            "description": "<p>Authorization JSON web token.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Header-Example:",
          "content": "{ \"Authorization\": \"Bearer eyJ0eXAiOiJKV1QiLCJh...\" }",
          "type": "json"
        }
      ]
    }
  }
] });
