import React from "react";

function Modal({ title, children, onClose }) {
  return (
      <div className="modal fade show" data-backdrop="static" data-keyboard="false" tabIndex="-1"
           style={{display: "block"}} aria-labelledby="staticBackdropLabel">
        <div className="modal-dialog">
          <div className="modal-content">
            <div className="modal-header">
              <h5 className="modal-title" id="staticBackdropLabel">{title}</h5>
              <button type="button" className="close" data-dismiss="modal" aria-label="Close" onClick={onClose}>
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div className="modal-body">
              {children}
            </div>
            <div className="modal-footer">
              <button type="button" className="btn btn-secondary" onClick={onClose}>Close</button>
            </div>
          </div>
        </div>
      </div>
  );
}

export default Modal;