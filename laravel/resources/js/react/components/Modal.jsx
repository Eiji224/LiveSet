import { createPortal } from "react-dom";

const Modal = ({ isOpen, children }) => {
    if (!isOpen) return null;

    return createPortal(
        <div className="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
            <div className="bg-white p-5 rounded-xl max-w-2/3 relative">
                {children}
            </div>
        </div>,
        document.body
    );
};

export default Modal;
