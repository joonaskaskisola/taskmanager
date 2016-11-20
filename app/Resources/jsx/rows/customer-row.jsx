import React from 'react';
import { render } from 'react-dom';

export default class CustomerRow extends React.Component {
    render() {
        return <div className="row" onClick={() => { this.props.viewRow(this.props.customer.id) }}>
            <div className="column">{ this.props.customer.name }</div>
            <div className="column">{ this.props.customer.businessId }</div>
            <div className="column">{ this.props.customer.streetAddress }</div>
            <div className="column">{ this.props.customer.country }</div>
        </div>
    }
}
