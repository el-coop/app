function randomString() {
    return Math.random().toString(36).substring(2, 15);
}

function parseTimestamp(timestampStr) {
    return new Date(new Date(timestampStr).getTime() + (new Date(timestampStr).getTimezoneOffset() * 60 * 1000));
}

class Sheet {
    constructor(date, rows, id) {
        if (!date) {
            date = new Date();
        } else if (typeof date === 'string') {
            date = parseTimestamp(date);
        }

        this.id = id || `temp_${randomString()}`;
        this.date = date;
        this.rows = rows || [];
        this.errors = {};
    }

    get x() {
        return this.date;
    }

    get y() {
        return this.total;
    }

    get total() {
        return this.rows.reduce((total, row) => {
            let value = 0;
            switch (row.action) {
                case '+':
                    value = parseFloat(row.amount);
                    break;
                case '-':
                    value = -parseFloat(row.amount);
                    break;
            }

            return total + value;
        }, 0);
    }

    get formattedDate() {
        let dateString = `${this.date.getFullYear()}-`;
        if (this.date.getUTCMonth() < 9) {
            dateString += `0${this.date.getMonth() + 1}`;
        } else {
            dateString += this.date.getMonth() + 1;
        }

        return dateString;
    }

    static CreateFromOld(oldSheet) {
        const date = new Date(oldSheet.date.getTime());
        date.setMonth(date.getMonth() + 1);
        return new Sheet(date, [{
            id: randomString(),
            label: 'Initial Money',
            action: '+',
            amount: oldSheet.total,
            comment: ''
        }]);
    }
}

export default Sheet;
