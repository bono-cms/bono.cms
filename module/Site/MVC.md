
MVC overview
====

The MVC (Model-View-Controller) pattern is often misunderstood on the web. Bono, however, follows a clean and accurate implementation of this architecture.

## Models

The **Model** layer is responsible for abstraction. It typically includes:

-   **Data abstraction** classes, such as mappers that handle database operations.
    
-   **Business logic** classes that process and manage rules.


These two concerns are often connected by a **Service** class, which acts as a bridge between data access and business logic.

**Example:**  

Let’s say we have a website where users can register and upload a profile photo. The model layer might include:

-   `UserMapper`: A data mapper that handles database operations like inserting or retrieving user records.
    
-   `ImageUploader`: A dedicated class responsible for image processing and file uploads.
    
-   `UserService`: a service class that acts as a bridge between `UserMapper` and `ImageUploader`, coordinating their functionality. It receives both as dependencies through its constructor.

In this scenario, the Model layer consists of three classes, but only `UserService` is directly used by the controllers.


## Views

A **View** is a class responsible for rendering templates. It formats and displays data provided by the controller.

## Controllers

**Controllers** handle incoming HTTP requests. They extract input data, pass it to the appropriate service objects, and then provide the resulting output to the view for rendering.