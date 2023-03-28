

const roles = {
    admin: 'admin',
    surgeon: 'surgeon',
    cardiologist: 'cardiologist',
    dispatcher: 'dispatcher',
    doctor_ambulance: 'doctor_ambulance',
    moderator: 'moderator hosp',
    moderator_smp: 'moderator smp',
    smp: 'smp',
}
const wait = 'Подождите';

const statuses = [
        {val: 0, label: 'Свободна', color: 'green'},
        {val: 1, label: 'Занята', color: 'red'},
        {val: 2, label: 'Условно занята', color: 'yellow'},
        {val: 10, label: 'Закрыто', color: 'gray'}
    ];

//: 'Станция СМП'
const hospital_type = {
    hospital: 1,
    smp: 2
}

// const server_url = 'http://127.0.0.1:3000';
const server_url = import.meta.env.VITE_SERVER_URL;

const timepiece = [
    '00:00','01:00','02:00','03:00','04:00','05:00','06:00','07:00',
    '08:00','09:00','10:00', '11:00','12:00','13:00','14:00','15:00',
    '16:00','17:00','18:00','19:00','20:00','21:00','22:00','23:00',
]







export {roles, wait, statuses, server_url, hospital_type, timepiece};
