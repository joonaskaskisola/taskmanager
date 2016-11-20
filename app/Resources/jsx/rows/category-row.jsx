import React from 'react';
import {render} from 'react-dom';

export default class CategoryRow extends React.Component {
    render() {
        return <div className="row" onClick={() => {this.props.viewRow(this.props.category.id)}}>
            <div className="column">{ this.props.category.name }</div>
        </div>
    }
}
