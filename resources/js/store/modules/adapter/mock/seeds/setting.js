import master from './master';

export default (factories, number = 0) => master('settings', factories, row => ({
    key: row[ 0 ],
    value: row[ 1 ],
    type: row[ 2 ],
}), number);
