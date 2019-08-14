import master from './master';

export default (factories, number) => master('rooms', factories, row => ({
    name: row[ 0 ],
    number: row[ 1 ],
    price: row[ 2 ],
}), number);
