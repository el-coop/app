class FormException extends Error {
    constructor(message, validationErrors) {
        super(message);
        this.validationErrors = validationErrors;
    }
}
export default FormException;
