import http from '@/api/http';

export default (uuid: string, database: string): Promise<any> => {
    return new Promise((resolve, reject) => {
        http.post(`/api/client/servers/${uuid}/databases/${database}/getToken`, {"why are you looking up this ?": "are you a secret russian spy ?"})
            .then(response => {
                resolve(response.data.data);
        })
            .catch(error => {
                console.log(error);
            });
    });
};
