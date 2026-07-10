import { EventSource } from 'eventsource';

const eventSourceUrl = process.env.SSE_URL ?? 'http://localhost:8000/controller/server-sent-event';
const eventSource = new EventSource(eventSourceUrl);

const log = (message) => {
    process.stdout.write(`[${new Date().toISOString()}] ${message}\n`);
};

log(`Connecting to ${eventSourceUrl}`);

eventSource.addEventListener('open', () => {
    log('Connected');
});

// Listen to all events (without a specific type)
eventSource.onmessage = (event) => {
    log(`Received: ${event.data}`);
};

// Listen to events with a specific type
eventSource.addEventListener('my-event', (event) => {
    log(`My event: ${JSON.stringify(JSON.parse(event.data))}`);
});

// Handle connection errors (the stream also ends with an error when the server closes)
eventSource.onerror = () => {
    if (eventSource.readyState === EventSource.CLOSED) {
        log('Connection closed');
        return;
    }

    log('SSE connection error, retrying...');
};
