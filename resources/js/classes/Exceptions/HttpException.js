class HttpException extends Error {
    constructor(message, response) {
        super(message);
        this.response = response;
    }
}

export default HttpException;
