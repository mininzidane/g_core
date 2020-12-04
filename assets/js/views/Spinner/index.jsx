import React from "react";

function Spinner() {
  return (
      <div className="spinner-border text-info ajax-loader" role="status">
        <span className="sr-only">Loading...</span>
      </div>
  );
}

export default Spinner;
