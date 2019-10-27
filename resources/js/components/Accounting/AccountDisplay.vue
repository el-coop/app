<template>
    <div class="card">
        <div class="card__header">
            <h5 class="title is-size-6">Total Money</h5>
        </div>
        <div class="title is-size-1 has-text-centered"><span class="is-size-5 is-nis"/><span v-text="total.toFixed(2)"/>
        </div>
        <table class="table">
            <tbody>
            <tr class="table__row">
                <td class="table__cell">Last 30 day total</td>
                <td class="table__cell is-nis" v-text="tableNumbers.total"/>
            </tr>
            <tr class="table__row">
                <td class="table__cell">Last 30 day income</td>
                <td class="table__cell is-nis" v-text="tableNumbers.income"/>
            </tr>
            <tr class="table__row">
                <td class="table__cell">Last 30 day expenditure</td>
                <td class="table__cell is-nis" v-text="tableNumbers.expenditure"/>
            </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
	export default {
		name: "AccountDisplay",

		props: {
			total: {
				required: true,
				type: Number
			},
			tableNumbers: {
				required: true,
				type: Object
			}
		}
	}
</script>
