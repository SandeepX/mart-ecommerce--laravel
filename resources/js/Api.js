class Api {

    getResponseWithParams(url, query, headers) {
        url = this.prepareUrl(url);
        const options = {
            headers: headers,
            params: query
        };
        return axios.get(url, options);
    }

    get(url) {
        url = this.prepareUrl(url);
        return axios.get(url);
    }

    getFakeApi(url, headers = {}) {
        url = this.prepareFakeUrl(url);
        const options = {
            headers: headers
        }
        return axios.get(url, options);
    }

    post(url, userData, headers = {}) {
        url = this.prepareUrl(url);
        const options = {
            headers: headers
        };
        return axios.post(url, userData, options);
    }

    delete(url, params, headers = {}) {

        url = this.prepareUrl(url);
        let config = {
            headers: headers
        };
        if (Object.entries(params).length !== 0) {
            config.data = params
        }
        return axios.delete(url, config);
    }

    getResponseType(url, headers = {}, responseType) {
        url = this.prepareUrl(url);
        const options = {
            headers: headers,
            responseType: responseType
        }

        return axios.get(url, options);
    }

    prepareUrl(endpoint) {
        return process.env.MIX_BASE_API_URL + endpoint;
    }

    prepareFakeUrl(endpoint) {
        return 'https://jsonblob.com/api/' + endpoint;
    }
}

export default new Api();
