import { SVGAttributes } from 'react';
import Scoutly from '@assets/scoutly.svg?react';

export default function AppLogoIcon(props: SVGAttributes<SVGElement>) {
    return (
        <Scoutly {...props} />
    );
}
