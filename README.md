## Requirements
```
   php v7+
   composer v5.6.0+
   xampp
```

## Installation
```
   composer install
```

## Database Initialization
```
   Open migrations folder in project
   Download the latest version of anuwrap.sql

   Open xampp
   import database to xampp

   Open test folder in project
   execute InsertRecords.sql in xampp
```
## Project Setup
```
   create .env file in root folder
```
   Define your environment variables
```
   DB_HOST=localhost
   DB_PORT=3306
   DB_NAME=databasename
   DB_DRIVER=mysql
   DB_USERNAME=yourusername
   DB_PASSWORD=yourpassword
   SECRET_API_KEY=yourapikey
```

## More information
```
   The project is highly opinionated by the author ( Adrian cris Gallano )

   i am not picking a fight for any php devs out there, 
   i simply feel comfortable in the way i structured my project

   Model -> Handles data
   Controller -> Orchestration
   Routes -> Url Dispatcher
   Services -> Business Logic
   Migrations -> Database Version Control
   public -> Entry point

```

## Accessing the API
### Authorizations
```
Level 1 Authorization:
User must login before having the ability to request for authorized resources  

Level 2 Authorization:
User can only modify their own information 
( so requesting a different id of a user is not permissible ).

```
### Routes 
```
User Routes:
   No Authorization
      [POST] /users -> create User => (username: str, firstname: str, lastname: str ,email: str, password: str, confirm_password: str)
      [GET] /users/{id:\d+} -> retrieve a single User
      [GET] /users -> get all users

   Authorized:
      [PUT] /users/{id:\d+} -> update a single User => ( first_name: str, last_name: str)
      [DELETE] /users/{id:\d+} -> delete a single User

Authenticated Routes:
   [POST] /token -> creates a Token (login) => ( email: str, password: str)

   Workspaces:

   [POST] /workspaces -> create a workspace => (name: str)
   [GET] /workspaces -> retrieves all workspace => (name: str)
   [GET] /workspaces/{workspaceId:\d+} -> retrieve a single workspace
   [PUT] /workspaces/{workspaceId:\d+} -> update a single workspace
   [DELETE] /workspaces/{workspaceId:\d+} -> delete a single workspace

   [GET] /users/{userId:\d+}/workspaces -> retrieves all workspaces with user
   [GET] /users/{userId:\d+}/workspaces/{workspaceId:\d+} -> retrieves a single workspace with user
   
   User Workspaces:
   [POST] /userworkspaces -> create userworkspace => (user_id: int, workspace_id: int, role_id: int)
   [GET] /users/{userId:\d+}/workspaces/{workspaceId:\d+}/userworkspaces => retrieves a single userworkspace
   [PUT] /users/{userId:\d+}/workspaces/{workspaceId:\d+}/userworkspaces => update a single userworkspace
   [DELETE] /users/{userId:\d+}/workspaces/{workspaceId:\d+}/userworkspaces => delete a single userworkspace

   Roles:
   [GET] /roles -> retrieves all roles
   [GET] /roles/{roleId:\d+} -> retrieves a single role

   Report Types:
   [GET] /report-types -> retrieves all report types
   [GET] /report-types/{reportTypeId:\d+} -> retrieves single report types

   Reports:
   [POST] /reports -> creates a report => (title: str, report_type_id: int, workspace_id: int)
   [GET] /reports -> retrieves all reports
   [GET] /reports/{reportId:\d+} -> retrieves a single report
   [PUT] /reports/{reportId:\d+} -> update a single report => (title: str, report_type_id: int, workspace_id: int)
   [DELETE] /reports/{reportId:\d+} -> delete a single report

   Faculty Matrix:
   [POST] /faculty-matrices -> creates a faculty matrix => (name: str, position: str, tenure: str, status: int, related_certificate: str, doctorate_degree: str, masters_degree: str, baccalaureate_degree: str, specification: str, enrollment_status: str, designation: str, teaching_experience: int, organization_membership: str, report_id: int)

   [GET] /faculty-matrices -> retrieves all faculty matrices 
   [GET] /faculty-matrices/{facultyMatrixId:\d+} -> retrieves a single faculty matrix
   [PUT] /faculty-matrices/{facultyMatrixId:\d+} -> update a single faculty matrix => (name: str, position: str, tenure: str, status: int, related_certificate: str, doctorate_degree: str, masters_degree: str, baccalaureate_degree: str, specification: str, enrollment_status: str, designation: str, teaching_experience: int, organization_membership: str, report_id: int)
   [DELETE] /faculty-matrices/{facultyMatrixId:\d+} -> deletes a faculty matrix


   Report Selection:
   POST /report-selections -> create a report selection => (annual_report_id: int, report_id: int)
   GET /report-selections -> retrieves all report selections
   GET /annual-reports/{annualReportId:\d+}/reports/{reportId:\d+}/report-selections -> retrieves a single report selection
   PUT /annual-reports/{annualReportId:\d+}/reports/{reportId:\d+}/report-selections -> updates a report selection => (annual_report_id: int, report_id: int)
   DELETE /annual-reports/{annualReportId:\d+}/reports/{reportId:\d+}/report-selections -> deletes a report selection


   Annual Report:
   [POST] /annual-reports -> creates an annual report => (title: str, description: str, workspace_id: int)
   [GET] /annual-reports -> retrieves all annual reports
   [GET] /annual-reports/{annualReportId:\d+} -> retrieves a single annual report
   [PUT] /annual-reports/{annualReportId:\d+} -> update a single annual report => (title: str, description: str, workspace_id: int)
   [DELETE] /annual-reports/{annualReportId:\d+} -> delete a single annual report

   Accomplishment Report:
   [POST] /accomplishment-reports -> creates an accomplishment report => (name_of_activity: str, date_of_activity: date, venue_of_activity: str, nature_of_activity: str, benefits_of_the_participants: str, narrative_report: str, image_name: str, report_id: int)
   [GET] /accomplishment-reports -> retrieves all accomplishment reports
   [GET] /accomplishment-reports/{accomplishmentReportId:\d+} -> retrieves a single accomplishment report
   [PUT] /accomplishment-reports/{accomplishmentReportId:\d+} -> update a single accomplishment report => (name_of_activity: str, date_of_activity: date, venue_of_activity: str, nature_of_activity: str, benefits_of_the_participants: str, narrative_report: str, image_name: str, report_id: int)
   [DELETE] /accomplishment-reports/{accomplishmentReportId:\d+} -> delete a single accomplishment report

```
