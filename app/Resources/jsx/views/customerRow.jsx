import React from 'react';
import { render } from 'react-dom';

export default class CustomerRow extends React.Component {
    render() {
        return <tr onClick={() => { this.props.viewRow(this.props.customer.id) }} className="pointertr">
            <td className="mdl-data-table__cell--non-numeric">{ this.props.customer.name }</td>
            <td className="mdl-data-table__cell--non-numeric">{ this.props.customer.businessId}</td>
            <td className="mdl-data-table__cell--non-numeric">{ this.props.customer.streetAddress }</td>
            <td className="mdl-data-table__cell--non-numeric">{ this.props.customer.country }</td>
        </tr>
    }
}
