import React from 'react';
import axios from 'axios';
import classNames from 'classnames';

import pageTitle from 'react-document-title-decorator';

import CSSModules from 'react-css-modules';
import styles from './style.css';

import { Form, Input, Textarea, Button } from 'components/Form';
import Link from 'components/Link';
import PortfolioCard from 'components/PortfolioCard';

@pageTitle('Contact')
@CSSModules(styles)
export default class Home extends React.Component {

    state = {
        form: {
            name: '',
            email: '',
            phone: '',
            message: '',
        },
        pending: false,
        status: {
            success: null,
            message: '',
        },
    }

    handleInputChange = ({ target }) => {
        const { name, value } = target;

        this.setState((state) => ({
            form: {
                ...state.form,
                [name]: value,
            },
        }));
    }

    render() {
        return (
            <article>
                <PortfolioCard
                    title='Contact'
                >
                    <Form
                        onSubmit={() => {
                            this.setState(() => ({ pending: true }));

                            const formData = this.state.form;
                            axios.post('/api/contact.php', formData).then(({ data }) => {
                                this.setState(() => ({ status: data }));
                            }).catch(({ response }) => {
                                this.setState(() => ({ status: response.data }));
                            }).then(() => {
                                this.setState(() => ({ pending: false }));
                            });
                        }}
                    >
                        <Input
                            title='Name'
                            name='name'
                            required
                            onChange={this.handleInputChange}
                        >
                            {this.state.name}
                        </Input>
                        <Input
                            title='Email Address'
                            name='email'
                            type='email'
                            required
                            onChange={this.handleInputChange}
                        >
                            {this.state.email}
                        </Input>
                        <Input
                            title='Phone Number'
                            name='phone'
                            onChange={this.handleInputChange}
                        >
                            {this.state.phone}
                        </Input>
                        <Textarea
                            title='Message'
                            name='message'
                            required
                            onChange={this.handleInputChange}
                        >
                            {this.state.message}
                        </Textarea>
                        <Button
                            className='button full-width'
                            type='submit'
                            pending={this.state.pending}
                        >
                            {this.state.status.success ? this.state.status.message : 'Submit'}
                        </Button>
                        <p styleName='error'>
                            {!this.state.status.success ? this.state.status.message : ''}
                        </p>
                    </Form>
                </PortfolioCard>
            </article>
        );
    }
}
