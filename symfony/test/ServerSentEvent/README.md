## Server-Sent Events (SSE) Client

A lightweight Node.js client script that connects to a Server-Sent Events endpoint and listens for real-time streaming data.

### Prerequisites

* Node.js: Version 24 or higher is required.

* Backend Server: Ensure your SSE server (e.g., your Symfony backend at http://localhost:8000) is active and reachable before starting the client.

### Setup

Navigate to the project directory in your terminal and install the required dependencies. This will install the `eventsource` package defined in the `package.json`.

```Bash
npm install
```

### Running the Script

Because this project uses the modern ES Module syntax to import dependencies, the script is executed using the .mjs extension.

Start the client by running:

```Bash
node client.mjs
```

### Expected Behavior

Once running, the script stays active in your terminal and listens for incoming streams:

* Standard Events: Logged as Received: <data>

* Custom Events: Events specifically named my-event are parsed as JSON and logged as My event: <data>

* Errors: If the server is unreachable or the connection drops, it will output SSE error: and safely close the connection.

To stop the script at any time, press Ctrl + C in your terminal.
