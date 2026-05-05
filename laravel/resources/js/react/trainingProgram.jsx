import '../bootstrap.js';
import React from 'react';
import ReactDOM from 'react-dom/client';
import {root} from "postcss";

const rootElement = document.getElementById('react-app');

if (rootElement) {
    const root = ReactDOM.createRoot(rootElement);
    root.render(<Form />);
}

function Form() {
    return (
        <div>
            <h1>Hello from React!</h1>
        </div>
    );
}
