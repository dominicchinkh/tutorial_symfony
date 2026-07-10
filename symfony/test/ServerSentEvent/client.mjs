import { EventSource } from 'eventsource';

const eventSource = new EventSource('http://localhost:8000/controller/server-sent-event');

// Listen to all events (without a specific type)
eventSource.onmessage = (event) => {
    console.log('Received:', event.data);
};

// Listen to events with a specific type
eventSource.addEventListener('my-event', (event) => {
    console.log('My event:', JSON.parse(event.data));
});

// Handle connection errors
eventSource.onerror = (error) => {
    console.error('SSE error:', error);
    eventSource.close();
};
