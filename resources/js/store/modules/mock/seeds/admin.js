import master from './master';

export default (factories, number) => master('admins', factories, row => ({
    name: row[ 0 ],
    email: row[ 1 ],
    password: row[ 2 ],
}), number);
