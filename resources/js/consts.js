

const roles = {
    admin: 'admin',
    surgeon: 'surgeon',
    cardiologist: 'cardiologist',
    dispatcher: 'dispatcher',
}
const wait = 'Подождите';

const statuses = [
        {val: 0, label: 'Свободна', color: 'green'},
        {val: 1, label: 'Занята', color: 'red'},
        {val: 2, label: 'Условно занята', color: 'yellow'}
    ];

// const server_url = 'http://127.0.0.1:3000';
const server_url = import.meta.env.VITE_SERVER_URL;







export {roles, wait, statuses, server_url};
