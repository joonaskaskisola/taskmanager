import React from 'react';
import { render } from 'react-dom';

export default class Row extends React.Component {
    render() {
        let fields = [], self = this;

        this.props.fields.forEach(function(field) {
            fields.push(<div className="column">{ self.props.row[field] }</div>);
        });

        return <div className="row" onClick={() => { this.props.viewRow(this.props.row.id) }}>
            {fields}
        </div>
    }
}
