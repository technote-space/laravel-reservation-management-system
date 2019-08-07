import { arrayToObject } from '../../utils/misc';

describe('arrayToObject', () => {
    [
        {
            args: [
                [1, 2, 3],
                {
                    getKey: ({ item }) => item * 2,
                },
            ],
            expected: {
                2: 1,
                4: 2,
                6: 3,
            },
        },
        {
            args: [
                [1, 2, 3],
                {
                    getItem: item => item * 2,
                },
            ],
            expected: {
                1: 2,
                2: 4,
                3: 6,
            },
        },
        {
            args: [
                [1, 2, 3],
                {
                    getItem: item => [
                        { id: item + '-1', item },
                        { id: item + '-2', item },
                    ],
                    getKey: ({ value }) => value.id,
                    isMultiple: true,
                },
            ],
            expected: {
                '1-1': { id: '1-1', item: 1 },
                '1-2': { id: '1-2', item: 1 },
                '2-1': { id: '2-1', item: 2 },
                '2-2': { id: '2-2', item: 2 },
                '3-1': { id: '3-1', item: 3 },
                '3-2': { id: '3-2', item: 3 },
            },
        },
    ].map(({ args, expected }, index) => {
        it('should return object ' + index, () => {
            expect(arrayToObject(...args)).toStrictEqual(expected);
        });
    });
});
