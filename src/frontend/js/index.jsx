import React, { Fragment } from "react";
import ReactDOM from "react-dom/client";
import App from "./App";

if (document.getElementById("awesomecoderDownloader") != null) {
  const root = ReactDOM.createRoot(document.getElementById("awesomecoderDownloader"));
  root.render(
    <App />
  );
}