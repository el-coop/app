class HttpResponse {
    constructor(status, headers, data) {
        this.status = status;
        this.headers = headers;
        this.data = data;
    }
}

export default HttpResponse;
